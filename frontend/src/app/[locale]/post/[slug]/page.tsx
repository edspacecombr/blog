import { Metadata } from 'next';
import { generateSeoMetadata } from '@/lib/seo';
import { buildArticleSchema } from '@/lib/schema';
import Breadcrumb from '@/components/public/cards/Breadcrumb';
import AuthorBox from '@/components/public/cards/AuthorBox';

// F5.9: ISR Configuration - Revalidate every hour for articles
export const revalidate = 3600;

export async function generateMetadata({
  params: { locale, slug },
}: {
  params: { locale: string; slug: string };
}): Promise<Metadata> {
  return generateSeoMetadata({
    title: `Artigo - ${slug}`,
    description: 'Leia o artigo completo',
    locale,
    slug: `/post/${slug}`,
    type: 'article',
    publishedTime: new Date().toISOString(),
    modifiedTime: new Date().toISOString(),
  });
}

export default function PostPage({
  params: { locale, slug },
}: {
  params: { locale: string; slug: string };
}) {
  // F5.11: Canonical URL already handled in generateMetadata
  const canonicalUrl = `${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:3000'}/${locale}/post/${slug}`;

  return (
    <article>
      <Breadcrumb
        items={[
          { label: 'Home', href: `/${locale}` },
          { label: 'Posts', href: `/${locale}/posts` },
          { label: 'Artigo', href: `/${locale}/post/${slug}` },
        ]}
      />
      
      <div className="max-w-3xl mx-auto">
        <h1 className="text-4xl font-bold mb-4">Artigo: {slug}</h1>
        
        <div className="prose prose-lg max-w-none mb-8">
          Conteúdo do artigo será carregado aqui via API
        </div>

        {/* JSON-LD Schema */}
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{
            __html: JSON.stringify(
              buildArticleSchema({
                title: `Artigo - ${slug}`,
                description: 'Leia o artigo completo',
                image: 'https://via.placeholder.com/1200x630',
                datePublished: new Date().toISOString(),
                dateModified: new Date().toISOString(),
                authorName: 'Blog Author',
                url: canonicalUrl,
              })
            ),
          }}
        />
      </div>
    </article>
  );
}
