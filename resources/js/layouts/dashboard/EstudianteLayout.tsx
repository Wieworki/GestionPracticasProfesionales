import React, { useState } from 'react';
import Sidebar from '@/components/dashboard/sidebar';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Menu } from 'lucide-react';

interface Props {
  children: React.ReactNode;
  nombre?: string;
}

export default function EstudianteLayout({ children, nombre }: Props) {
  const { auth } = usePage<SharedData>().props;
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const navItems = [
    { label: 'Mis Datos', href: '/estudiante/perfil', isEnabled: true },
    { label: 'Ver ofertas disponibles', href: '/estudiante/ofertas/index', isEnabled: auth.user.habilitado },
    { label: 'Ver Empresas', href: '/estudiante/empresas', isEnabled: auth.user.habilitado },
    { label: 'Mis Postulaciones', href: '/estudiante/postulaciones/index', isEnabled: auth.user.habilitado },
  ];

  return (
    <AppLayout>
     <div className="flex min-h-screen bg-gray-50 text-gray-800 relative overflow-x-auto overflow-y-hidden md:overflow-y-auto">
        <button
          className="absolute top-4 left-4 md:hidden z-50 bg-white p-2 rounded-lg shadow-md border border-gray-200"
          onClick={() => setSidebarOpen(!sidebarOpen)}
        >
          <Menu size={20} />
        </button>

        <Sidebar
          nombre={nombre}
          navItems={navItems}
          isOpen={sidebarOpen}
          onClose={() => setSidebarOpen(false)}
        />

        <main
          className={`flex-1 p-6 transition-all duration-300 ${
            sidebarOpen ? 'blur-sm md:blur-none' : ''
          } md:ml-64`}
        >
          {children}
        </main>
      </div>
    </AppLayout>
  );
}
