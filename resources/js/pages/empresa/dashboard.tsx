import React from 'react';
import EmpresaLayout from '@/layouts/dashboard/EmpresaLayout';
import DisabledAccountNotice from '@/components/dashboard/disabledAccountNotice';
import WelcomeCard from '@/components/dashboard/welcomeCard';

interface Props {
    empresa: {
        nombre: string;
        habilitado: boolean;
    };
    mensajeBienvenida: string;
}

export default function Dashboard({ empresa, mensajeBienvenida }: Props) {
    return (
        <EmpresaLayout nombre={empresa.nombre} habilitado={empresa.habilitado}>
            <div className="space-y-6">
                {!empresa.habilitado && <DisabledAccountNotice />}

                <WelcomeCard nombreUsuario={empresa.nombre} mensaje={mensajeBienvenida} />

                {empresa.habilitado && (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                            <h2 className="text-lg font-semibold mb-2 text-blue-700">
                                Crear nueva oferta
                            </h2>
                            <p className="text-gray-600 mb-4">
                                Publicá una nueva práctica profesional y encontrá estudiantes interesados.
                            </p>
                            <a
                                href="/empresa/ofertas/crear"
                                className="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            >
                                Crear oferta
                            </a>
                        </div>

                        <div className="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                            <h2 className="text-lg font-semibold mb-2 text-blue-700">
                                Gestionar mis ofertas
                            </h2>
                            <p className="text-gray-600 mb-4">
                                Editá o eliminá tus ofertas activas y revisá las postulaciones.
                            </p>
                            <a
                                href="/empresa/ofertas"
                                className="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            >
                                Ver ofertas
                            </a>
                        </div>
                    </div>
                )}
            </div>
        </EmpresaLayout>
    );
}