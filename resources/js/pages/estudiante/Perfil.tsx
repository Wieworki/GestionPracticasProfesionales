import React from "react";
import { Head, Link } from "@inertiajs/react";
import EstudianteLayout from '@/layouts/dashboard/EstudianteLayout';
import { Button } from "@/components/ui/button";

interface Props {
    estudiante: {
        nombre: string;
        apellido?: string;
        email: string;
        dni: string;
        telefono: string;
    };
}

export default function Perfil({ estudiante }: Props) {
    return (
        <EstudianteLayout nombre={estudiante.nombre}>
            <Head title="Mi perfil" />

            <div className="max-w-2xl mx-auto bg-white shadow p-6 rounded-xl space-y-6">
                <h1 className="text-2xl font-semibold text-blue-700 mb-4">
                    Mis Datos
                </h1>

                <div className="space-y-4 text-gray-700">
                    <p><strong>Nombre:</strong> {estudiante.nombre}</p>
                    <p><strong>Apellido:</strong> {estudiante.apellido}</p>
                    <p><strong>Email:</strong> {estudiante.email}</p>
                    <p><strong>DNI:</strong> {estudiante.dni}</p>
                    <p><strong>Telefono:</strong> {estudiante.telefono}</p>
                </div>

                <div className="flex justify-end gap-3 pt-6 border-t">
                    <Link href="/estudiante/perfil/edit">
                        <Button>Modificar datos</Button>
                    </Link>
                    <Link href="/estudiante/cambiar-password">
                        <Button variant="outline">Cambiar contraseña</Button>
                    </Link>
                    <Link href="/estudiante/dashboard">
                        <Button variant="secondary">Volver</Button>
                    </Link>
                </div>
            </div>
        </EstudianteLayout>
    );
}
