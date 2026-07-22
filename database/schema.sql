-- IRC Zimbabwe (Interactive Research & Classroom) Supabase Schema
-- Run this in your Supabase SQL Editor to initialize pgvector and all tables

-- Enable Vector Extension for RAG
CREATE EXTENSION IF NOT EXISTS vector;

-- 1. Users Table (Maps to Supabase Auth or Local Guest)
CREATE TABLE IF NOT EXISTS public.users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    email TEXT UNIQUE NOT NULL,
    full_name TEXT,
    phone_number TEXT,
    tier TEXT DEFAULT 'free', -- 'free', 'daily_pass', 'scholar_monthly'
    credits INTEGER DEFAULT 50,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 2. Workspaces (Subjects / Courses)
CREATE TABLE IF NOT EXISTS public.workspaces (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES public.users(id) ON DELETE CASCADE,
    name TEXT NOT NULL,
    description TEXT,
    category TEXT DEFAULT 'ZIMSEC / General', -- ZIMSEC O-Level, A-Level, University, Professional
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 3. Documents Vault
CREATE TABLE IF NOT EXISTS public.documents (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    workspace_id UUID REFERENCES public.workspaces(id) ON DELETE CASCADE,
    file_name TEXT NOT NULL,
    file_path TEXT NOT NULL,
    file_size INTEGER NOT NULL,
    file_type TEXT DEFAULT 'application/pdf',
    status TEXT DEFAULT 'processing', -- 'processing', 'ready', 'failed'
    total_pages INTEGER DEFAULT 1,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 4. Document Chunks & Vector Embeddings (Gemini 768-dim embeddings)
CREATE TABLE IF NOT EXISTS public.document_chunks (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    document_id UUID REFERENCES public.documents(id) ON DELETE CASCADE,
    workspace_id UUID REFERENCES public.workspaces(id) ON DELETE CASCADE,
    chunk_index INTEGER NOT NULL,
    page_number INTEGER DEFAULT 1,
    content TEXT NOT NULL,
    embedding vector(768),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Index for vector search (HNSW index for fast similarity lookups)
CREATE INDEX IF NOT EXISTS document_chunks_embedding_idx 
ON public.document_chunks 
USING hnsw (embedding vector_cosine_ops);

-- 5. RPC Function for Vector Similarity Search (RAG Engine)
CREATE OR REPLACE FUNCTION match_document_chunks (
  query_embedding vector(768),
  match_threshold float,
  match_count int,
  p_workspace_id uuid
)
RETURNS TABLE (
  id uuid,
  document_id uuid,
  content text,
  page_number int,
  similarity float
)
LANGUAGE plpgsql
AS $$
BEGIN
  RETURN QUERY
  SELECT
    dc.id,
    dc.document_id,
    dc.content,
    dc.page_number,
    1 - (dc.embedding <=> query_embedding) AS similarity
  FROM public.document_chunks dc
  WHERE dc.workspace_id = p_workspace_id
    AND 1 - (dc.embedding <=> query_embedding) > match_threshold
  ORDER BY dc.embedding <=> query_embedding
  LIMIT match_count;
END;
$$;

-- 6. Chat Sessions & Messages
CREATE TABLE IF NOT EXISTS public.chat_sessions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    workspace_id UUID REFERENCES public.workspaces(id) ON DELETE CASCADE,
    title TEXT DEFAULT 'New Study Session',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.chat_messages (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    session_id UUID REFERENCES public.chat_sessions(id) ON DELETE CASCADE,
    sender TEXT NOT NULL, -- 'user' or 'ai'
    message TEXT NOT NULL,
    citations JSONB DEFAULT '[]'::jsonb, -- Array of {page_number, document_id, snippet}
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 7. Active Recall Flashcards (Spaced Repetition System - SRS)
CREATE TABLE IF NOT EXISTS public.flashcards (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    workspace_id UUID REFERENCES public.workspaces(id) ON DELETE CASCADE,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    page_reference INTEGER,
    ease_factor FLOAT DEFAULT 2.5,
    interval_days INTEGER DEFAULT 1,
    reviews_count INTEGER DEFAULT 0,
    next_review_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 8. Feynman Technique Interactive Tutor Logs
CREATE TABLE IF NOT EXISTS public.feynman_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    workspace_id UUID REFERENCES public.workspaces(id) ON DELETE CASCADE,
    topic TEXT NOT NULL,
    student_explanation TEXT NOT NULL,
    ai_feedback TEXT NOT NULL,
    mastery_score INTEGER DEFAULT 0, -- 0 to 100%
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 9. Local Payments Table (Paynow EcoCash & InnBucks Integration)
CREATE TABLE IF NOT EXISTS public.payments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES public.users(id) ON DELETE CASCADE,
    paynow_reference TEXT UNIQUE,
    poll_url TEXT,
    amount DECIMAL(10, 2) NOT NULL,
    currency TEXT DEFAULT 'USD', -- 'USD' or 'ZIG'
    payment_method TEXT DEFAULT 'EcoCash', -- 'EcoCash', 'InnBucks', 'OneMoney', 'Card'
    phone_number TEXT,
    status TEXT DEFAULT 'pending', -- 'pending', 'paid', 'failed'
    pass_type TEXT DEFAULT 'daily_pass', -- 'daily_pass', 'monthly_pass', 'credits_topup'
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
