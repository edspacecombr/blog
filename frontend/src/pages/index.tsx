import Head from 'next/head';
import Link from 'next/link';

export default function Index() {
  return (
    <>
      <Head>
        <title>Welcome - Professional Multilingual Blog</title>
        <meta name="description" content="Build your SEO-optimized, multilingual blog platform" />
      </Head>
      <div className="min-h-screen bg-gradient-to-br from-blue-600 to-blue-800 flex flex-col items-center justify-center px-4 py-12">
        <div className="max-w-2xl text-center text-white">
          <h1 className="text-5xl font-bold mb-6">Professional Multilingual Blog</h1>
          <p className="text-xl mb-12 text-blue-100">
            An SEO-optimized, easy-to-use blogging platform with built-in multilingual support and AdSense integration
          </p>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <div className="bg-white/10 rounded-lg p-6 backdrop-blur">
              <h3 className="text-xl font-bold mb-3">✨ Features</h3>
              <ul className="text-sm text-left space-y-2">
                <li>• Multi-language support</li>
                <li>• SEO optimized</li>
                <li>• Automatic sitemap & hreflang</li>
                <li>• AdSense ready</li>
                <li>• Visual editor (GrapesJS)</li>
              </ul>
            </div>

            <div className="bg-white/10 rounded-lg p-6 backdrop-blur">
              <h3 className="text-xl font-bold mb-3">🚀 Quick Start</h3>
              <p className="text-sm text-left mb-4">Get started in 5 minutes with our guided setup process</p>
              <Link href="/setup" className="inline-block bg-white text-blue-600 px-4 py-2 rounded font-bold hover:bg-blue-50">
                Start Setup
              </Link>
            </div>
          </div>

          <div className="space-x-4">
            <Link
              href="/login"
              className="inline-block bg-white text-blue-600 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition"
            >
              Login
            </Link>
            <Link
              href="/register"
              className="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-400 transition"
            >
              Create Account
            </Link>
          </div>
        </div>
      </div>
    </>
  );
}
