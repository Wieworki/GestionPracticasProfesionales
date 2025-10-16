import React from "react";
import { Head, Link } from "@inertiajs/react";
import AdministrativoLayout from '@/layouts/dashboard/AdministrativoLayout';
import { Button } from "@/components/ui/button";

interface Props {
    administrativo: {
        nombre: string;
        apellido?: string;
        email: string;
        telefono: string;
    };
}

export default function Perfil({ administrativo }: Props) {
    return (
        <AdministrativoLayout nombre={administrativo.nombre}>
            <Head title="Mi perfil" />

            <div className="max-w-2xl mx-auto bg-white shadow p-6 rounded-xl space-y-6">
                <h1 className="text-2xl font-semibold text-blue-700 mb-4">
                    Mis Datos
                </h1>

                <div className="space-y-4 text-gray-700">
                    <p><strong>Nombre:</strong> {administrativo.nombre}</p>
                    <p><strong>Apellido:</strong> {administrativo.apellido}</p>
                    <p><strong>Email:</strong> {administrativo.email}</p>
                    <p><strong>Telefono:</strong> {administrativo.telefono}</p>
                </div>

                <div className="flex justify-end gap-3 pt-6 border-t">
                    <Link href="/administrativo/perfil/edit">
                        <Button>Modificar datos</Button>
                    </Link>
                    <Link href="/administrativo/cambiar-password">
                        <Button variant="outline">Cambiar contraseña</Button>
                    </Link>
                    <Link href="/administrativo/dashboard">
                        <Button variant="secondary">Volver</Button>
                    </Link>
                </div>
            </div>
        </AdministrativoLayout>
    );
}
