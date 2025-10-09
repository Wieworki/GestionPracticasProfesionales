import React from 'react';
import SidebarItem from '@/components/dashboard/sidebarItem';
import { NavItem } from '@/Types';
import { Link, router } from "@inertiajs/react";
import { LogOut } from "lucide-react";
import { Button } from '@/components/ui/button';

interface Props {
    nombre?: string;
    navItems: NavItem[];
}

export default function Sidebar({ nombre, navItems }: Props) {

    const handleLogout = () => {
        router.post(route("logout"));
    };

    return (
        <aside className="w-64 bg-white shadow-md border-r border-gray-200 flex flex-col">
            <div className="p-4 border-b">
                <h2 className="text-xl font-semibold text-blue-700">
                    {nombre || 'Empresa'}
                </h2>
            </div>
            <nav className="flex-1 p-4 space-y-2">
                {navItems.map((item) => (
                    <SidebarItem label={item.label} href={item.href} habilitado={item.isEnabled} key={item.href}  />
                ))}
            </nav>

            <div className="border-t p-4">
                <Button
                    variant="outline"
                    className="w-full flex items-center justify-center gap-2 text-red-600 border-red-400 hover:bg-red-100"
                    onClick={handleLogout}
                >
                    <LogOut size={18} />
                    Salir
                </Button>
            </div>

        </aside>
    );
}