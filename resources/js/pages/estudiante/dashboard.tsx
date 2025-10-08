import React from 'react';
import EstudianteLayout from '@/layouts/dashboard/EstudianteLayout';
import DisabledAccountNotice from '@/components/dashboard/disabledAccountNotice';
import WelcomeCard from '@/components/dashboard/welcomeCard';

interface Props {
    estudiante: {
        nombre: string;
        habilitado: boolean;
    };
    mensajeBienvenida: string;
}

export default function Dashboard({ estudiante, mensajeBienvenida }: Props) {
    return (
        <EstudianteLayout nombre={estudiante.nombre} habilitado={estudiante.habilitado}>
            <div className="space-y-6">
                {!estudiante.habilitado && <DisabledAccountNotice />}

                <WelcomeCard nombreUsuario={estudiante.nombre} mensaje={mensajeBienvenida} />

                {estudiante.habilitado && (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                            <h2 className="text-lg font-semibold mb-2 text-blue-700">
                                Ver ofertas
                            </h2>
                            <p className="text-gray-600 mb-4">
                                Ir al listado de ofertas de práctica profesional disponibles.
                            </p>
                            <a
                                href="/estudiante/ofertas"
                                className="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            >
                                Ver ofertas
                            </a>
                        </div>

                        <div className="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                            <h2 className="text-lg font-semibold mb-2 text-blue-700">
                                Postulaciones hechas
                            </h2>
                            <p className="text-gray-600 mb-4">
                                Gestion de las postulaciones hechas.
                            </p>
                            <a
                                href="/estudiante/postulaciones"
                                className="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            >
                                Mis postulaciones
                            </a>
                        </div>
                    </div>
                )}
            </div>
        </EstudianteLayout>
    );
}