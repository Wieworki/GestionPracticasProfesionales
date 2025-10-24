import React from 'react';
import Sidebar from '@/components/dashboard/sidebar';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

interface Props {
    children: React.ReactNode;
    nombre?: string;
}

export default function AdministrativoLayout({ children, nombre }: Props) {
    const { auth } = usePage<SharedData>().props;   // No need to manually set the auth user from the controller

    const navItems = [
        { label: 'Mis Datos', href: '/administrativo/perfil', isEnabled: true },      // La opcion de "mis datos" esta disponible aun si el usuario no esta habilitado
        { label: 'Empresas', href: '/administrativo/empresas', isEnabled: auth.user.habilitado },
        { label: 'Estudiantes', href: '/administrativo/estudiantes', isEnabled: auth.user.habilitado },
        { label: 'Ofertas', href: '/administrativo/ofertas', isEnabled: auth.user.habilitado },
        { label: 'Administracion', href: '/administrativo/administracion', isEnabled: auth.user.habilitado },
    ];

    return (
        <AppLayout>
            <div className="flex min-h-screen bg-gray-50 text-gray-800">
                <Sidebar nombre={nombre} navItems={navItems} />
                <main className="flex-1 p-6">{children}</main>
            </div>
        </AppLayout>
    );
}