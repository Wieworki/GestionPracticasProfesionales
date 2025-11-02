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

export default function EmpresaLayout({ children, nombre }: Props) {
    const { auth } = usePage<SharedData>().props;   // No need to manually set the auth user from the controller
    const [sidebarOpen, setSidebarOpen] = useState(false);

    const navItems = [
        { label: 'Mis Datos', href: '/empresa/perfil', isEnabled: true },      // La opcion de "mis datos" esta disponible aun si el usuario no esta habilitado
        { label: 'Nueva Oferta', href: '/empresa/ofertas/nueva', isEnabled: auth.user.habilitado },
        { label: 'Mis Ofertas', href: '/empresa/ofertas/index', isEnabled: auth.user.habilitado },
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