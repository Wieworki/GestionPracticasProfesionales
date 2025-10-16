import React from 'react';
import { useForm, Head, Link } from '@inertiajs/react';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import InputError from '@/components/input-error';

export default function ChangePassword() {
    const { data, setData, post, processing, errors } = useForm({
        current_password: '',
        new_password: '',
        new_password_confirmation: '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('password.guardar'));
    };

    return (
        <div className="max-w-md mx-auto bg-white shadow rounded-xl p-8 mt-10">
            <Head title="Cambiar Contraseña" />
            <h1 className="text-2xl font-semibold text-blue-700 mb-6">
                Cambiar Contraseña
            </h1>

            <form onSubmit={submit} className="space-y-5">
                <div>
                    <Label htmlFor="current_password">Contraseña actual</Label>
                    <Input
                        id="current_password"
                        type="password"
                        value={data.current_password}
                        onChange={(e) => setData('current_password', e.target.value)}
                    />
                    <InputError message={errors.current_password} />
                </div>

                <div>
                    <Label htmlFor="new_password">Nueva contraseña</Label>
                    <Input
                        id="new_password"
                        type="password"
                        value={data.new_password}
                        onChange={(e) => setData('new_password', e.target.value)}
                    />
                    <InputError message={errors.new_password} />
                </div>

                <div>
                    <Label htmlFor="new_password_confirmation">
                        Repetir nueva contraseña
                    </Label>
                    <Input
                        id="new_password_confirmation"
                        type="password"
                        value={data.new_password_confirmation}
                        onChange={(e) =>
                            setData('new_password_confirmation', e.target.value)
                        }
                    />
                    <InputError message={errors.new_password_confirmation} />
                </div>

                <div className="flex justify-between pt-6">
                    <Link
                        href={route('dashboard')}
                        className="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100"
                    >
                        Volver
                    </Link>
                    <Button
                        type="submit"
                        disabled={processing}
                        className="bg-blue-600 hover:bg-blue-700 text-white"
                    >
                        Guardar cambios
                    </Button>
                </div>
            </form>
        </div>
    );
}
