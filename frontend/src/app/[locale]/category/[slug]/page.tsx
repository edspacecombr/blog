import { Metadata } from 'next';
import { generateSeoMetadata } from '@/lib/seo';
import PostCard from '@/components/public/cards/PostCard';
import Pagination from '@/components/public/cards/Pagination';

// F5.9: ISR Configuration - Revalidate every 6 hours for categories
export const revalidate = 21600;

export async function generateMetadata({
  params: { locale, slug },
  searchParams: { page = '1' },
}: {
  params: { locale: string; slug: string };
  searchParams: { page?: string };
}): Promise<Metadata> {
  const pageNum = parseInt(page as string) || 1;
  const titleSuffix = pageNum > 1 ? ` - Página ${pageNum}` : '';

  return generateSeoMetadata({
    title: `Categoria: ${slug}${titleSuffix}`,
    description: `Veja todos os artigos da categoria ${slug}`,
    locale,
    slug: `/category/${slug}?page=${page}`,
  });
}

export default function CategoryPage({
  params: { locale, slug },
  searchParams: { page = '1' },
}: {
  params: { locale: string; slug: string };
  searchParams: { page?: string };
}) {
  const pageNum = parseInt(page as string) || 1;

  // F5.11: SEO-friendly pagination - noindex on deep pages
  const showRobotsNoindex = pageNum > 5;
  const canonicalUrl = `${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:3000'}/${locale}/category/${slug}${pageNum > 1 ? `?page=${pageNum}` : ''}`;

  return (
    <div>
      {/* F5.11: Add noindex for deep pagination */}
      {showRobotsNoindex && (
        <meta name="robots" content="noindex, follow" />
      )}

      <div className="max-w-6xl mx-auto px-4">
        <h1 className="text-3xl font-bold mb-2">Categoria: {slug}</h1>
        {pageNum > 1 && (
          <p className="text-gray-500 mb-8">Página {pageNum}</p>
        )}

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
          <div className="text-center py-12 text-gray-500 col-span-full">
            Posts da categoria serão carregados aqui via API
          </div>
        </div>

        {/* F5.11: SEO-friendly Pagination with query params */}
        <div className="flex justify-center gap-4 mb-12">
          {pageNum > 1 && (
            <a
              href={`/${locale}/category/${slug}?page=${pageNum - 1}`}
              rel="prev"
              className="px-6 py-2 bg-gray-200 rounded hover:bg-gray-300"
            >
              ← Anterior
            </a>
          )}

          <span className="px-6 py-2 bg-blue-600 text-white rounded font-semibold">
            {pageNum}
          </span>

          <a
            href={`/${locale}/category/${slug}?page=${pageNum + 1}`}
            rel="next"
            className="px-6 py-2 bg-gray-200 rounded hover:bg-gray-300"
          >
            Próximo →
          </a>
        </div>

        {/* Canonical URL for search engines */}
        <link rel="canonical" href={canonicalUrl} />
      </div>
    </div>
  );
}
