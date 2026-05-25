export default function BaseLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="container p-4 bg-gray-300 mx-auto min-h-screen">
      {children}
    </div>
  )
}