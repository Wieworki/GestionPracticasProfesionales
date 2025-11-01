import React from 'react';
import SidebarItem from '@/components/dashboard/sidebarItem';
import { NavItem } from '@/Types';
import { router } from "@inertiajs/react";
import { LogOut, X } from "lucide-react";
import { Button } from '@/components/ui/button';

interface Props {
  nombre?: string;
  navItems: NavItem[];
  isOpen?: boolean;
  onClose?: () => void;
}

export default function Sidebar({ nombre, navItems, isOpen = true, onClose }: Props) {
  const handleLogout = () => {
    router.post(route("logout"));
  };

  return (
    <>
      {isOpen && (
        <div
          className="fixed inset-0 bg-gray-800/40 backdrop-blur-sm z-40 md:hidden"
          onClick={onClose}
        ></div>
      )}

      <aside
        className={`fixed md:static z-50 bg-white shadow-md border-r border-gray-200 flex flex-col h-screen transition-transform duration-300
          w-64 ${isOpen ? 'translate-x-0' : '-translate-x-full'} md:translate-x-0`}
      >
        <div className="p-4 border-b flex justify-between items-center">
          <h2 className="text-xl font-semibold text-blue-700">
            {nombre || 'Usuario'}
          </h2>
          <button onClick={onClose} className="md:hidden">
            <X size={20} />
          </button>
        </div>

        <nav className="flex-1 p-4 space-y-2 overflow-y-auto">
          {navItems.map((item) => (
            <SidebarItem
              label={item.label}
              href={item.href}
              habilitado={item.isEnabled}
              key={item.href}
            />
          ))}
        </nav>

        {/* Logout */}
        <div className="border-t p-4 mt-auto">
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
    </>
  );
}
