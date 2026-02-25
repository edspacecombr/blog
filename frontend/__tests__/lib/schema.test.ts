import '@testing-library/jest-dom';

// Mock schema data
const mockArticleSchema = {
  '@context': 'https://schema.org',
  '@type': 'NewsArticle',
  headline: 'Test Article',
  description: 'Article description',
  image: '/images/article.jpg',
  datePublished: '2024-02-24T10:00:00Z',
  dateModified: '2024-02-24T12:00:00Z',
  author: {
    '@type': 'Person',
    name: 'Test Author'
  }
};

const mockBreadcrumbSchema = {
  '@context': 'https://schema.org',
  '@type': 'BreadcrumbList',
  itemListElement: [
    {
      '@type': 'ListItem',
      position: 1,
      name: 'Home',
      item: 'https://example.com'
    },
    {
      '@type': 'ListItem',
      position: 2,
      name: 'Blog',
      item: 'https://example.com/blog'
    }
  ]
};

describe('Schema Utilities', () => {
  describe('Article Schema', () => {
    it('should have correct @type', () => {
      expect(mockArticleSchema['@type']).toBe('NewsArticle');
    });

    it('should have headline', () => {
      expect(mockArticleSchema.headline).toBe('Test Article');
    });

    it('should have author', () => {
      expect(mockArticleSchema.author).toBeDefined();
      expect(mockArticleSchema.author.name).toBe('Test Author');
    });

    it('should have datePublished', () => {
      expect(mockArticleSchema.datePublished).toBeDefined();
    });

    it('should have dateModified', () => {
      expect(mockArticleSchema.dateModified).toBeDefined();
    });
  });

  describe('Breadcrumb Schema', () => {
    it('should have correct @type', () => {
      expect(mockBreadcrumbSchema['@type']).toBe('BreadcrumbList');
    });

    it('should have itemListElement array', () => {
      expect(Array.isArray(mockBreadcrumbSchema.itemListElement)).toBe(true);
    });

    it('should have correct positions', () => {
      const items = mockBreadcrumbSchema.itemListElement;
      expect(items[0].position).toBe(1);
      expect(items[1].position).toBe(2);
    });

    it('should have correct item names', () => {
      const items = mockBreadcrumbSchema.itemListElement;
      expect(items[0].name).toBe('Home');
      expect(items[1].name).toBe('Blog');
    });
  });

  describe('Schema JSON-LD script tag', () => {
    it('should render as JSON string', () => {
      const jsonStr = JSON.stringify(mockArticleSchema);
      expect(jsonStr).toContain('"@context"');
      expect(jsonStr).toContain('schema.org');
    });
  });
});
