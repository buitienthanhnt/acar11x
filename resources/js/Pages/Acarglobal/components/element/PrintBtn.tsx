import { useCallback } from "react";

const PrintBtn = ({ title = 'In' }: { title: string }) => {
  const onPrint = useCallback(() => {
    window.print();
  }, [])

  return (
    <div className="no-print absolute right-1 top-10 bg-gray-600 hover:bg-gray-900 px-4 p-1 rounded-md text-white"
      onClick={onPrint}>
      {title}
    </div>
  )
}

export default PrintBtn;