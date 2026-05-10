import { HomeIcon } from "@heroicons/react/24/solid";
import { Link } from "@inertiajs/react";
import TopBar from "./TopBar";


export default function ContentLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="flex flex-col min-h-screen w-full">
      <TopBar></TopBar>
      {children}
      <Link className="no-print z-10" href={'/acar/'}>
        <HomeIcon className="no-print size-10 absolute bottom-10 right-10 text-gray-500 hover:text-gray-900"></HomeIcon></Link>
    </div>
  )
}
