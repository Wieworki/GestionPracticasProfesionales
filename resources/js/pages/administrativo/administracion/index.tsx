import React from 'react';
import AdministrativoLayout from '@/layouts/dashboard/AdministrativoLayout';
import DisabledAccountNotice from '@/components/dashboard/disabledAccountNotice';
import { Link } from '@inertiajs/react';
import { Users } from 'lucide-react';

interface Props {
    administrativo: {
        nombre: string;
        habilitado: boolean;
    };
}

export default function Dashboard({ administrativo }: Props) {
    const { nombre, habilitado } = administrativo;

    const features = [
        {
            title: 'Crear usuario administrativo',
            description: 'Formulario de creacion de un nuevo usuario administrativo de la misma facultad.',
            href: '/administrativo/administracion/crearUsuario',
            icon: <Users className="text-blue-600 w-6 h-6" />,
        }
    ];

    return (
        <AdministrativoLayout nombre={nombre}>
            <div className="space-y-8 animate-fadeIn">
                {!habilitado && <DisabledAccountNotice />}

                {habilitado && (
                    <section>
                        <h2 className="text-2xl font-semibold text-gray-800 mb-4">
                            Opciones de administracion
                        </h2>

                        <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            {features.map((item) => (
                                <div
                                    key={item.href}
                                    className="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200"
                                >
                                    <div className="flex items-center justify-between mb-3">
                                        <div className="flex items-center gap-3">
                                            <div className="p-3 bg-blue-50 rounded-full">
                                                {item.icon}
                                            </div>
                                            <h3 className="text-lg font-semibold text-gray-800">
                                                {item.title}
                                            </h3>
                                        </div>
                                    </div>
                                    <p className="text-gray-600 text-sm mb-5">
                                        {item.description}
                                    </p>
                                    <Link
                                        href={item.href}
                                        className="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 transition"
                                    >
                                        Ir
                                        <span className="group-hover:translate-x-1 transition-transform">→</span>
                                    </Link>
                                </div>
                            ))}
                        </div>
                    </section>
                )}
            </div>
        </AdministrativoLayout>
    );
}