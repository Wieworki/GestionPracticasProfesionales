import React from 'react';
import Sidebar from '@/components/dashboard/sidebar';

interface Props {
    children: React.ReactNode;
    nombre?: string;
    habilitado: boolean;
}

export default function EmpresaLayout({ children, nombre, habilitado }: Props) {
    return (
        <div className="flex min-h-screen bg-gray-50 text-gray-800">
            <Sidebar nombre={nombre} habilitado={habilitado} />
            <main className="flex-1 p-6">{children}</main>
        </div>
    );
}