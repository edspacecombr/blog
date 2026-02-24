import { revalidatePath } from 'next/cache';
import { NextRequest, NextResponse } from 'next/server';

/**
 * F5.9: Webhook endpoint for ISR revalidation
 * Called by Laravel API when content is published/updated
 */
export async function POST(request: NextRequest) {
  // Verify secret token
  const secret = request.headers.get('X-Revalidate-Secret');
  if (secret !== process.env.REVALIDATE_SECRET) {
    return NextResponse.json({ error: 'Invalid secret' }, { status: 401 });
  }

  try {
    const body = await request.json();
    const { paths } = body;

    if (!Array.isArray(paths)) {
      return NextResponse.json({ error: 'Invalid paths' }, { status: 400 });
    }

    // Revalidate each path
    for (const path of paths) {
      revalidatePath(path);
    }

    return NextResponse.json({
      revalidated: true,
      paths,
      now: Date.now(),
    });
  } catch (error) {
    console.error('Revalidation error:', error);
    return NextResponse.json(
      { error: 'Failed to revalidate' },
      { status: 500 }
    );
  }
}
