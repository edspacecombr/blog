import { z } from 'zod';

// Setup Wizard Validators
export const setupStep1Schema = z.object({
  blog_name: z.string().min(3, 'Nome do blog deve ter pelo menos 3 caracteres'),
  blog_url: z.string().url('URL inválida'),
  blog_description: z.string().min(10, 'Descrição deve ter pelo menos 10 caracteres'),
  blog_email: z.string().email('Email inválido'),
});

export const setupStep2Schema = z.object({
  logo_url: z.string().url('URL do logo inválida').optional(),
  favicon_url: z.string().url('URL do favicon inválida').optional(),
  primary_color: z.string().regex(/^#[0-9A-F]{6}$/i, 'Cor primária inválida'),
  secondary_color: z.string().regex(/^#[0-9A-F]{6}$/i, 'Cor secundária inválida'),
});

export const setupStep3Schema = z.object({
  default_language: z.string().min(2, 'Selecione um idioma padrão'),
  active_languages: z.array(z.string()).min(1, 'Ative pelo menos um idioma'),
});

export const setupStep4Schema = z.object({
  blog_niche: z.string().min(3, 'Nicho do blog é obrigatório'),
  active_layout: z.enum(['Layout1Clean', 'Layout2Magazine', 'Layout3Minimal']),
});

// Post Validators
export const postSchema = z.object({
  title: z.string().min(5, 'Título deve ter pelo menos 5 caracteres'),
  slug: z.string().optional(),
  excerpt: z.string().min(10, 'Resumo deve ter pelo menos 10 caracteres'),
  content: z.string().min(50, 'Conteúdo deve ter pelo menos 50 caracteres'),
  category_id: z.number().int('Selecione uma categoria'),
  author_id: z.number().int('Selecione um autor'),
  language: z.string().default('pt'),
  status: z.enum(['draft', 'published', 'scheduled']).default('draft'),
  featured_image_url: z.string().url().optional(),
  scheduled_at: z.string().optional(),
  seo_title: z.string().optional(),
  seo_description: z.string().optional(),
});

// Category Validators
export const categorySchema = z.object({
  name: z.string().min(2, 'Nome deve ter pelo menos 2 caracteres'),
  slug: z.string().optional(),
  description: z.string().optional(),
  parent_id: z.number().optional(),
  language: z.string().default('pt'),
});

// Author Validators
export const authorSchema = z.object({
  name: z.string().min(2, 'Nome deve ter pelo menos 2 caracteres'),
  email: z.string().email('Email inválido'),
  bio: z.string().optional(),
  avatar_url: z.string().url().optional(),
  social_links: z.record(z.string(), z.string()).optional(),
});

// Language Validators
export const languageSchema = z.object({
  code: z.string().length(2, 'Código deve ter exatamente 2 caracteres'),
  name: z.string().min(2, 'Nome do idioma é obrigatório'),
  native_name: z.string().min(2, 'Nome nativo é obrigatório'),
  is_default: z.boolean().default(false),
  is_active: z.boolean().default(true),
});

// Settings Validators
export const settingsSchema = z.object({
  blog_name: z.string().min(3),
  blog_url: z.string().url(),
  blog_description: z.string(),
  email: z.string().email(),
  seo_title_pattern: z.string().optional(),
  seo_meta_description_pattern: z.string().optional(),
  og_image_url: z.string().url().optional(),
  active_layout: z.string().optional(),
  adsense_id: z.record(z.string(), z.string()).optional(),
  robots_txt_content: z.string().optional(),
});

export type SetupStep1 = z.infer<typeof setupStep1Schema>;
export type SetupStep2 = z.infer<typeof setupStep2Schema>;
export type SetupStep3 = z.infer<typeof setupStep3Schema>;
export type SetupStep4 = z.infer<typeof setupStep4Schema>;
export type Post = z.infer<typeof postSchema>;
export type Category = z.infer<typeof categorySchema>;
export type Author = z.infer<typeof authorSchema>;
export type Language = z.infer<typeof languageSchema>;
export type Settings = z.infer<typeof settingsSchema>;
