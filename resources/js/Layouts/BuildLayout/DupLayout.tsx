import { Link } from "@inertiajs/react";
import Baselayout from "../BaseLayout";
import FootContent from "./Components/FootContent";
import TopContent from "./Components/TopContent";
import { HomeIcon } from '@heroicons/react/24/solid';
import { FunctionComponent } from "react";

interface DupLayoutProps {
	children: React.ReactNode
}

const DupLayout: FunctionComponent<DupLayoutProps> = ({ children }: any) => {
	return (
		<Baselayout>
			<TopContent />
			{children}
			<FootContent />
			<Link href={'/'} className="rounded-full justify-center items-center flex bg-blue-gray-300 p-2 bottom-20 left-20 fixed z-50">
				<HomeIcon className="w-10 h-10 text-black"></HomeIcon>
			</Link>
		</Baselayout>
	);
}

export default DupLayout;
