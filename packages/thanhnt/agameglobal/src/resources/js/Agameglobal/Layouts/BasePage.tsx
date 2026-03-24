export default function BasePage({ children, className = '' }) {
    return (
        <div className={`flex-1 bg-blue-gray-300 min-h-screen p-2 container mx-auto flex flex-col gap-4 ${className}`}>
            {children}
        </div>
    )
}
