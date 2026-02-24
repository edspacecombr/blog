interface SchemaOrg {
  '@context': string;
  '@type': string;
  [key: string]: any;
}

export function buildArticleSchema(params: {
  title: string;
  description: string;
  image: string;
  datePublished: string;
  dateModified: string;
  authorName: string;
  authorUrl?: string;
  url: string;
}): SchemaOrg {
  return {
    '@context': 'https://schema.org',
    '@type': 'Article',
    headline: params.title,
    description: params.description,
    image: params.image,
    datePublished: params.datePublished,
    dateModified: params.dateModified,
    author: {
      '@type': 'Person',
      name: params.authorName,
      ...(params.authorUrl && { url: params.authorUrl }),
    },
    url: params.url,
  };
}

export function buildBreadcrumbSchema(breadcrumbs: Array<{ name: string; url: string }>): SchemaOrg {
  return {
    '@context': 'https://schema.org',
    '@type': 'BreadcrumbList',
    itemListElement: breadcrumbs.map((crumb, index) => ({
      '@type': 'ListItem',
      position: index + 1,
      name: crumb.name,
      item: crumb.url,
    })),
  };
}

export function buildAuthorSchema(params: {
  name: string;
  url?: string;
  image?: string;
  bio?: string;
}): SchemaOrg {
  return {
    '@context': 'https://schema.org',
    '@type': 'Person',
    name: params.name,
    ...(params.url && { url: params.url }),
    ...(params.image && { image: params.image }),
    ...(params.bio && { description: params.bio }),
  };
}

export function buildOrgSchema(params: {
  name: string;
  url: string;
  logo: string;
  description: string;
}): SchemaOrg {
  return {
    '@context': 'https://schema.org',
    '@type': 'Organization',
    name: params.name,
    url: params.url,
    logo: params.logo,
    description: params.description,
  };
}

export function buildFaqSchema(faqs: Array<{ question: string; answer: string }>): SchemaOrg {
  return {
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    mainEntity: faqs.map((faq) => ({
      '@type': 'Question',
      name: faq.question,
      acceptedAnswer: {
        '@type': 'Answer',
        text: faq.answer,
      },
    })),
  };
}

export function getSchemaScriptTag(schema: SchemaOrg): string {
  return `<script type="application/ld+json">${JSON.stringify(schema)}</script>`;
}
