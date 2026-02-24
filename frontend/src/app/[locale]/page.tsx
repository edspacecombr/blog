import { Metadata } from 'next';
import { generateSeoMetadata } from '@/lib/seo';
import PostCard from '@/components/public/cards/PostCard';

// F5.9: ISR Configuration - Revalidate every hour
export const revalidate = 3600;

export async function generateMetadata({ params: { locale } }: { params: { locale: string } }): Promise<Metadata> {
  return generateSeoMetadata({
    title: 'Home | Blog',
    description: 'Bem-vindo ao nosso blog multilíngue com histórias, insights e conhecimento',
    locale,
    type: 'website',
  });
}

export default function Home({ params: { locale } }: { params: { locale: string } }) {
  // F5.12: Dynamic layout selection
  const activeLayout = 'Layout1Clean'; // TODO: Load from API settings

  return (
    <div>
      {/* Hero Section */}
      <section className="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16 mb-12">
        <div className="max-w-6xl mx-auto px-4">
          <h1 className="text-5xl font-bold mb-4">Bem-vindo ao Blog</h1>
          <p className="text-xl text-blue-100">
            Histórias, insights e conhecimento compartilhado
          </p>
        </div>
      </section>

      {/* Posts Grid - F5.10: Using optimized images */}
      <div className="max-w-6xl mx-auto px-4 mb-12">
        <h2 className="text-3xl font-bold mb-8">Últimas Publicações</h2>
        
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {/* Posts will be loaded from API */}
          <div className="text-center py-12 text-gray-500 col-span-full">
            Carregando posts... (integração API em Fase 6)
          </div>
        </div>

        {/* F5.11: SEO Pagination */}
        <div className="flex justify-center gap-4 mt-12">
          <button className="px-6 py-2 bg-gray-200 rounded hover:bg-gray-300">
            ← Anterior
          </button>
          <span className="px-6 py-2 bg-blue-600 text-white rounded">1</span>
          <button className="px-6 py-2 bg-gray-200 rounded hover:bg-gray-300">
            Próximo →
          </button>
        </div>
      </div>
    </div>
  );
}
