import React from 'react';
import { Link, usePage } from '@inertiajs/react';

interface Props {
    label: string;
    href: string;
    habilitado: boolean;
}

export default function sidebarItem({ label, href, habilitado }: Props) {

    const { url } = usePage();

    return (
        <div>
            {habilitado == true && (
            <Link
                    key={href}
                    href={href}
                    className={`block rounded-lg px-4 py-2 hover:bg-blue-50 transition ${
                        url.startsWith(href)
                            ? 'bg-blue-100 text-blue-800 font-medium'
                            : 'text-gray-700'
                    }`}
                >
                    {label}
            </Link>
            )}
            {habilitado != true && (
                <div className={`block rounded-lg px-4 py-2 hover:bg-blue-50 transition opacity-50`} >
                    {label}
                </div>
            )}
        </div>
    );
}