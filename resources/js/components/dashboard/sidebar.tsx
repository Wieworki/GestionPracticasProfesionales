import React from 'react';
import SidebarItem from '@/components/dashboard/sidebarItem';

interface Props {
    nombre?: string;
    habilitado: boolean;
}

export default function Sidebar({ nombre, habilitado }: Props) {
    const navItems = [
        { label: 'Mis Datos', href: '/empresa/perfil', habilitado: true },      // La opcion de "mis datos" esta disponible aun si el usuario no esta habilitado
        { label: 'Nueva Oferta', href: '/empresa/ofertas/crear' },
        { label: 'Mis Ofertas', href: '/empresa/ofertas' },
    ];

    return (
        <aside className="w-64 bg-white shadow-md border-r border-gray-200 flex flex-col">
            <div className="p-4 border-b">
                <h2 className="text-xl font-semibold text-blue-700">
                    {nombre || 'Empresa'}
                </h2>
            </div>
            <nav className="flex-1 p-4 space-y-2">
                {navItems.map((item) => (
                    <SidebarItem label={item.label} href={item.href} habilitado={item.habilitado || habilitado}  />
                ))}
            </nav>
        </aside>
    );
}