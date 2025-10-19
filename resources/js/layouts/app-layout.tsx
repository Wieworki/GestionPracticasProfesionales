import React, { useEffect, useState } from 'react'
import { usePage } from '@inertiajs/react'
import { Transition } from '@headlessui/react'

export default function AppLayout({ children }: { children: React.ReactNode }) {
  const { flash } = usePage().props as { flash?: { success?: string; error?: string } }

  const [message, setMessage] = useState<string | null>(null)
  const [type, setType] = useState<'success' | 'error' | null>(null)
  const [show, setShow] = useState(false)

  useEffect(() => {
    if (flash?.success) {
      setMessage(flash.success)
      setType('success')
      setShow(true)
    } else if (flash?.error) {
      setMessage(flash.error)
      setType('error')
      setShow(true)
    }
  }, [flash])

  // Ocultar automáticamente después de 4 segundos
  useEffect(() => {
    if (show) {
      const timer = setTimeout(() => setShow(false), 4000)
      return () => clearTimeout(timer)
    }
  }, [show])

  return (
    <div className="min-h-screen bg-gray-100">
      {/* Toast */}
      <div className="fixed top-5 right-5 z-50 flex flex-col space-y-2">
        <Transition
          show={show}
          enter="transition-opacity duration-300"
          enterFrom="opacity-0 translate-y-2"
          enterTo="opacity-100 translate-y-0"
          leave="transition-opacity duration-500"
          leaveFrom="opacity-100"
          leaveTo="opacity-0 translate-y-2"
        >
          <div
            className={`rounded-lg shadow-lg px-4 py-3 text-white ${
              type === 'success' ? 'bg-green-600' : 'bg-red-600'
            }`}
          >
            {message}
          </div>
        </Transition>
      </div>

      <main className="p-6">{children}</main>
    </div>
  )
}
