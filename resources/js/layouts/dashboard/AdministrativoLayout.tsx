import React from 'react';
import Sidebar from '@/components/dashboard/sidebar';

interface Props {
    children: React.ReactNode;
    nombre?: string;
    habilitado: boolean;
}

export default function AdministrativoLayout({ children, nombre, habilitado }: Props) {

    const navItems = [
        { label: 'Mis Datos', href: '/administrativo/perfil', isEnabled: true },      // La opcion de "mis datos" esta disponible aun si el usuario no esta habilitado
        { label: 'Empresas', href: '/administrativo/empresas', isEnabled: habilitado },
        { label: 'Estudiantes', href: '/administrativo/estudiantes', isEnabled: habilitado },
        { label: 'Ofertas', href: '/administrativo/ofertas', isEnabled: habilitado },
        { label: 'Administracion', href: '/administrativo/administracion', isEnabled: habilitado },
    ];

    return (
        <div className="flex min-h-screen bg-gray-50 text-gray-800">
            <Sidebar nombre={nombre} navItems={navItems} />
            <main className="flex-1 p-6">{children}</main>
        </div>
    );
}