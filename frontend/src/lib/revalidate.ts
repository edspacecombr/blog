/**
 * F5.9: ISR Revalidation Service
 * Handles on-demand revalidation via webhook from Laravel backend
 */

export async function revalidatePost(slug: string) {
  try {
    const response = await fetch('/api/revalidate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Revalidate-Secret': process.env.REVALIDATE_SECRET || 'secret',
      },
      body: JSON.stringify({
        paths: [
          `/pt/post/${slug}`,
          `/en/post/${slug}`,
          `/es/post/${slug}`,
        ],
      }),
    });

    return response.ok;
  } catch (error) {
    console.error('Revalidation failed:', error);
    return false;
  }
}

export async function revalidateCategory(slug: string) {
  try {
    const response = await fetch('/api/revalidate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Revalidate-Secret': process.env.REVALIDATE_SECRET || 'secret',
      },
      body: JSON.stringify({
        paths: [
          `/pt/category/${slug}`,
          `/en/category/${slug}`,
          `/es/category/${slug}`,
        ],
      }),
    });

    return response.ok;
  } catch (error) {
    console.error('Revalidation failed:', error);
    return false;
  }
}

export async function revalidateHome() {
  try {
    const response = await fetch('/api/revalidate', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Revalidate-Secret': process.env.REVALIDATE_SECRET || 'secret',
      },
      body: JSON.stringify({
        paths: ['/pt', '/en', '/es'],
      }),
    });

    return response.ok;
  } catch (error) {
    console.error('Revalidation failed:', error);
    return false;
  }
}
