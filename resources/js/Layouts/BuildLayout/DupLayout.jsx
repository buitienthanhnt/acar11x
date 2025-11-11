import { Link } from "@inertiajs/react";
import Baselayout from "../BaseLayout";
import FootContent from "./Components/FootContent";
import TopContent from "./Components/TopContent";
import { HomeIcon } from '@heroicons/react/24/solid';

const DupLayout = ({ children, topMenu }) => {
	return (
		<Baselayout>
			<TopContent />
			{children}
			<FootContent />
			<Link href={'/'} className="rounded-full justify-center items-center flex bg-blue-gray-300 p-2 bottom-20 left-20 fixed">
				<HomeIcon className="w-14 h-14 text-deep-purple-600"></HomeIcon>
			</Link>
		</Baselayout>
	);
}

export default DupLayout;
