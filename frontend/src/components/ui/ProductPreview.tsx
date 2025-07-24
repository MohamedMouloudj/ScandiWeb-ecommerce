interface ProductPreviewProps {
  image: string;
  name: string;
  price: number;
}

export default function ProductPreview({
  image,
  name,
  price,
}: ProductPreviewProps) {
  return (
    <div className="bg-white p-4 rounded shadow-sm w-64">
      <img
        src={image}
        alt={name}
        className="w-full h-64 object-cover rounded mb-4 border border-gray-100"
      />
      <div className="text-gray-700 text-lg mb-1">{name}</div>
      <div className="text-xl font-bold text-gray-900">${price.toFixed(2)}</div>
    </div>
  );
}
