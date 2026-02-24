import React from 'react';

interface Layout1CleanProps {
  children: React.ReactNode;
}

export default function Layout1Clean({ children }: Layout1CleanProps) {
  return (
    <div className="flex flex-col min-h-screen">
      <header className="bg-white shadow-md sticky top-0 z-50">
        <nav className="container mx-auto px-4 py-4 flex justify-between items-center">
          <div className="text-2xl font-bold text-blue-600">Blog</div>
          <div className="flex gap-6">
            <a href="/" className="text-gray-600 hover:text-blue-600">Início</a>
            <a href="/blog" className="text-gray-600 hover:text-blue-600">Blog</a>
            <a href="/about" className="text-gray-600 hover:text-blue-600">Sobre</a>
            <a href="/contact" className="text-gray-600 hover:text-blue-600">Contato</a>
          </div>
        </nav>
      </header>

      <main className="container mx-auto px-4 py-12 flex-1">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2">{children}</div>
          <aside className="space-y-6">
            <div className="bg-gray-100 rounded-lg p-6">
              <h3 className="font-bold mb-4">Categorias</h3>
              <ul className="space-y-2">
                <li><a href="#" className="text-blue-600 hover:underline">Tecnologia</a></li>
                <li><a href="#" className="text-blue-600 hover:underline">Negócios</a></li>
              </ul>
            </div>
          </aside>
        </div>
      </main>

      <footer className="bg-gray-900 text-white mt-12">
        <div className="container mx-auto px-4 py-8 text-center">
          <p>© 2026 Todos os direitos reservados</p>
        </div>
      </footer>
    </div>
  );
}
