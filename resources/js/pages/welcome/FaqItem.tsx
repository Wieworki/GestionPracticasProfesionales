import React, { useState } from "react";

// We define a type for the props this component expects
// When creating a "FaqItem", we must recieve these 3 props
type FaqItemProps = {
  id: string;
  titulo: string;
  descripcion: string;
};

// Functional component, we destructure the props in the function signature
// So now we have assigned id, titulo and descripcion
export default function FaqItem({ id, titulo, descripcion }: FaqItemProps) {
  const [open, setOpen] = useState(false);  //"Open" is a boolean, and "setOpen" is the function that updates it

  const toggle = () => setOpen((prev) => !prev);    // Event handler, when it is called, it triggers the function setOpen, and changes the state

  return (
    <li
      key={id}
      className="relative flex flex-col gap-2 py-2 before:absolute before:top-1/2 before:bottom-0 before:left-[0.4rem] before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A]"
    >
      {/* Title clickable */}
      <div
        className="cursor-pointer ml-1 inline-flex items-center space-x-1 font-medium text-[#f53003] underline underline-offset-4 dark:text-[#FF4433]"
        onClick={toggle}
      >
        <span>{titulo}</span>
        <svg
          width={10}
          height={11}
          viewBox="0 0 10 11"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
          className={`h-2.5 w-2.5 transition-transform duration-200 ${
            open ? "rotate-45" : ""
          }`}
        >
          <path
            d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001"
            stroke="currentColor"
            strokeLinecap="square"
          />
        </svg>
      </div>

    {/* Short circuit code, used for conditional rendering */}
      {open && (
        <div className="ml-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
          {descripcion}
        </div>
      )}
    </li>
  );
}