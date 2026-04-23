export default function ContentLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="flex flex-col flex-1 min-h-screen w-full p-4 bg-gray-300">
      {children}
    </div>
  )
}