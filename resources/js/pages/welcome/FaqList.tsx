import React from "react";
import FaqItem from "./FaqItem";

type FaqInciso = {
  id: string;
  titulo: string;
  descripcion: string;
};

type FaqListProps = {
  faq: FaqInciso[];
};

export default function FaqList({ faq }: FaqListProps) {
  return (
    <ul className="mb-4 flex flex-col lg:mb-6">
      {faq.map((item) => (
        <FaqItem
          key={item.id}
          id={item.id}
          titulo={item.titulo}
          descripcion={item.descripcion}
        />
      ))}
    </ul>
  );
}