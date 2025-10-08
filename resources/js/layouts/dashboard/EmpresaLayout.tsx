import React from 'react';
import Sidebar from '@/components/dashboard/sidebar';

interface Props {
    children: React.ReactNode;
    nombre?: string;
    habilitado: boolean;
}

export default function EmpresaLayout({ children, nombre, habilitado }: Props) {

    const navItems = [
        { label: 'Mis Datos', href: '/empresa/perfil', isEnabled: true },      // La opcion de "mis datos" esta disponible aun si el usuario no esta habilitado
        { label: 'Nueva Oferta', href: '/empresa/ofertas/crear', isEnabled: habilitado },
        { label: 'Mis Ofertas', href: '/empresa/ofertas', isEnabled: habilitado },
    ];

    return (
        <div className="flex min-h-screen bg-gray-50 text-gray-800">
            <Sidebar nombre={nombre} navItems={navItems} />
            <main className="flex-1 p-6">{children}</main>
        </div>
    );
}