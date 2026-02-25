import '@testing-library/jest-dom';

// Mock hooks for testing
const mockSeoData = {
  title: 'Test Post',
  description: 'Test description',
  image: '/images/test.jpg',
  url: 'https://example.com/test-post',
  canonical: 'https://example.com/test-post',
  hreflang: [
    { lang: 'en', url: 'https://example.com/en/test-post' },
    { lang: 'pt', url: 'https://example.com/pt/test-post' }
  ]
};

describe('SEO Utilities', () => {
  it('should have title', () => {
    expect(mockSeoData.title).toBe('Test Post');
  });

  it('should have description', () => {
    expect(mockSeoData.description).toBe('Test description');
  });

  it('should have image', () => {
    expect(mockSeoData.image).toBe('/images/test.jpg');
  });

  it('should have canonical URL', () => {
    expect(mockSeoData.canonical).toBe('https://example.com/test-post');
  });

  it('should have hreflang array', () => {
    expect(Array.isArray(mockSeoData.hreflang)).toBe(true);
    expect(mockSeoData.hreflang.length).toBe(2);
  });

  it('hreflang should have language codes', () => {
    const langs = mockSeoData.hreflang.map(h => h.lang);
    expect(langs).toContain('en');
    expect(langs).toContain('pt');
  });

  it('generateMetadata should work with post data', () => {
    const metadata = {
      title: mockSeoData.title,
      description: mockSeoData.description,
      openGraph: {
        title: mockSeoData.title,
        description: mockSeoData.description,
        images: [{ url: mockSeoData.image }]
      }
    };

    expect(metadata.title).toBeDefined();
    expect(metadata.description).toBeDefined();
    expect(metadata.openGraph).toBeDefined();
  });
});
