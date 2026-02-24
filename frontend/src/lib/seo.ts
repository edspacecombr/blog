import { Metadata } from 'next';

interface SeoParams {
  title: string;
  description: string;
  locale: string;
  slug?: string;
  image?: string;
  type?: 'article' | 'website';
  publishedTime?: string;
  modifiedTime?: string;
  authors?: string[];
}

export function generateSeoMetadata(params: SeoParams): Metadata {
  const { title, description, locale, slug, image, type = 'website', publishedTime, modifiedTime, authors } = params;
  
  const baseUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:3000';
  const canonical = slug ? `${baseUrl}/${locale}${slug}` : `${baseUrl}/${locale}`;
  
  const alternates = {
    canonical,
    languages: {
      'pt': `${baseUrl}/pt${slug || ''}`,
      'en': `${baseUrl}/en${slug || ''}`,
      'es': `${baseUrl}/es${slug || ''}`,
    },
  };

  const openGraph = {
    title,
    description,
    url: canonical,
    type: type as 'article' | 'website',
    images: image ? [{ url: image, width: 1200, height: 630, alt: title }] : undefined,
    ...(type === 'article' && {
      publishedTime,
      modifiedTime,
      authors,
    }),
  };

  const twitter = {
    card: 'summary_large_image' as const,
    title,
    description,
    images: image ? [image] : undefined,
  };

  return {
    title,
    description,
    alternates,
    openGraph,
    twitter,
    robots: {
      index: true,
      follow: true,
      nocache: false,
      'max-snippet': -1,
      'max-image-preview': 'large',
      'max-video-preview': -1,
    },
  };
}

export function buildHreflang(slug: string, locales: string[]) {
  const baseUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:3000';
  
  return locales.map((locale) => ({
    rel: 'alternate',
    hrefLang: locale,
    href: `${baseUrl}/${locale}${slug}`,
  }));
}

export function buildCanonicalUrl(locale: string, slug: string) {
  const baseUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:3000';
  return `${baseUrl}/${locale}${slug}`;
}

export function buildOpenGraphImage(imageUrl: string, title: string, description: string) {
  return {
    url: imageUrl,
    width: 1200,
    height: 630,
    alt: title,
    type: 'image/jpeg',
  };
}
