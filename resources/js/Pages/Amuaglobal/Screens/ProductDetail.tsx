export default function ProductDetail({ product }: { product: any }) {
  return (
    <div className="container mx-auto">
      <h1 className="text-2xl font-bold mb-4">{product.name}</h1>
      <img src={product.image_path} alt={product.name} className="w-64 h-64 object-cover mb-4" />
      <p className="text-xl font-bold text-green-500 mb-2">{product.price} vnđ</p>
      <p className="text-gray-700">{product.description}</p>
    </div>
  )
}