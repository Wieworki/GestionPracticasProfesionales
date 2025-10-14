import React from 'react';
import AdministrativoLayout from '@/layouts/dashboard/AdministrativoLayout';
import DisabledAccountNotice from '@/components/dashboard/disabledAccountNotice';
import WelcomeCard from '@/components/dashboard/welcomeCard';
import { Link } from '@inertiajs/react';
import { Building2, Users, FileText, UserPlus } from 'lucide-react';

interface Props {
    administrativo: {
        nombre: string;
        habilitado: boolean;
    };
    mensajeBienvenida: string;
}

export default function Dashboard({ administrativo, mensajeBienvenida }: Props) {
    const { nombre, habilitado } = administrativo;

    const features = [
        {
            title: 'Ver ofertas',
            description: 'Explorá y gestioná las ofertas de prácticas profesionales creadas en el sistema.',
            href: '/administrativo/ofertas',
            icon: <FileText className="text-blue-600 w-6 h-6" />,
        },
        {
            title: 'Empresas registradas',
            description: 'Accedé al listado completo de empresas registradas.',
            href: '/administrativo/empresas',
            icon: <Building2 className="text-blue-600 w-6 h-6" />,
        },
        {
            title: 'Estudiantes registrados',
            description: 'Consultá y gestioná la información de los estudiantes registrados.',
            href: '/administrativo/estudiantes',
            icon: <Users className="text-blue-600 w-6 h-6" />,
        },
        {
            title: 'Administracion',
            description: 'Acceder a las opciones de administracion.',
            href: '/administrativo/administracion',
            icon: <UserPlus className="text-blue-600 w-6 h-6" />,
        },
    ];

    return (
        <AdministrativoLayout nombre={nombre}>
            <div className="space-y-8 animate-fadeIn">
                {!habilitado && <DisabledAccountNotice />}

                <WelcomeCard nombreUsuario={nombre} mensaje={mensajeBienvenida} />

                {habilitado && (
                    <section>
                        <h2 className="text-2xl font-semibold text-gray-800 mb-4">
                            Panel de gestión
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