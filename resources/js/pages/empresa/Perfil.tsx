import React from "react";
import { Head, Link } from "@inertiajs/react";
import EmpresaLayout from '@/layouts/dashboard/EmpresaLayout';
import { Button } from "@/components/ui/button";

interface Props {
    empresa: {
        nombre: string;
        email: string;
        cuit: string;
        descripcion?: string;
        sitio_web?: string;
        telefono?: string;
    };
}

export default function Perfil({ empresa }: Props) {
    return (
        <EmpresaLayout nombre={empresa.nombre}>
            <Head title="Mi perfil" />

            <div className="max-w-2xl mx-auto bg-white shadow p-6 rounded-xl space-y-6">
                <h1 className="text-2xl font-semibold text-blue-700 mb-4">
                    Mis Datos
                </h1>

                <div className="space-y-4 text-gray-700">
                    <p><strong>Nombre:</strong> {empresa.nombre}</p>
                    <p><strong>Email:</strong> {empresa.email}</p>
                    <p><strong>CUIT:</strong> {empresa.cuit}</p>
                    <p><strong>Descripción:</strong> {empresa.descripcion || "—"}</p>
                    <p><strong>Sitio Web:</strong> {empresa.sitio_web || "—"}</p>
                    <p><strong>Telefono:</strong> {empresa.telefono}</p>
                </div>

                <div className="flex justify-end gap-3 pt-6 border-t">
                    <Link href="/empresa/perfil/edit">
                        <Button>Modificar datos</Button>
                    </Link>
                    <Link href="/password/cambiar">
                        <Button variant="outline">Cambiar contraseña</Button>
                    </Link>
                    <Link href="/empresa/dashboard">
                        <Button variant="secondary">Volver</Button>
                    </Link>
                </div>
            </div>
        </EmpresaLayout>
    );
}
