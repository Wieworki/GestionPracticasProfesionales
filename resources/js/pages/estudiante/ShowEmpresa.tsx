import React from 'react';
import { Link } from '@inertiajs/react';
import EstudianteLayout from '@/layouts/dashboard/EstudianteLayout';
import { Button } from '@/components/ui/button';

interface Props {
    empresa: {
        id: number;
        nombre: string;
        descripcion: string;
        email_contacto: string;
        sitio_web: string;
    };
    estudiante: {
        nombre: string;
    };
}

export default function ShowEmpresa({
    empresa,
    estudiante
}: Props ) {

    return (
        <EstudianteLayout nombre={estudiante.nombre}>
            <div className="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow">
                <h1 className="text-2xl font-semibold mb-4 text-gray-800">Detalle de la Empresa</h1>

                <div className="space-y-3">
                    <div>
                        <strong>Nombre:</strong>
                        <p>{empresa.nombre}</p>
                    </div>

                    <div>
                        <strong>Descripción:</strong>
                        <p>{empresa.descripcion}</p>
                    </div>

                    <div>
                        <strong>Email de contacto:</strong>
                        <p>{empresa.email_contacto}</p>
                    </div>

                    <div>
                        <strong>Sitio web:</strong>
                        <a href={empresa.sitio_web} className="text-blue-600 underline" target="_blank">
                            {empresa.sitio_web}
                        </a>
                    </div>
                </div>

                <div className="flex justify-between mt-8">
                    <Link href="/estudiante/empresas">
                        <Button variant="secondary">Volver</Button>
                    </Link>

                    <Link href={`/estudiante/ofertas?empresa_id=${empresa.id}`}>
                        <Button variant="default">Ver ofertas</Button>
                    </Link>
                </div>
            </div>
        </EstudianteLayout>
    );
}
