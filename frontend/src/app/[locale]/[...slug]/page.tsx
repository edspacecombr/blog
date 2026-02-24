import { generateSeoMetadata } from '@/lib/seo';
import Breadcrumb from '@/components/public/cards/Breadcrumb';

export async function generateMetadata({
  params: { locale, slug },
}: {
  params: { locale: string; slug: string[] };
}) {
  const pageSlug = '/' + slug.join('/');
  return generateSeoMetadata({
    title: 'Página | Blog',
    description: 'Conteúdo da página',
    locale,
    slug: pageSlug,
  });
}

export default function Page({
  params: { locale, slug },
}: {
  params: { locale: string; slug: string[] };
}) {
  const pageSlug = slug.join('/');

  return (
    <div>
      <Breadcrumb
        items={[
          { label: 'Home', href: `/${locale}` },
          { label: 'Página', href: `/${locale}/${pageSlug}` },
        ]}
      />

      <article className="prose prose-lg max-w-none">
        <h1>Página</h1>
        <p>Conteúdo da página será carregado aqui</p>
      </article>
    </div>
  );
}
