import {
	IconButton,
	SpeedDial,
	SpeedDialHandler,
	SpeedDialContent,
	SpeedDialAction,
} from "@material-tailwind/react";
import {
	PlusIcon,
	HomeIcon,
	CogIcon,
	Square3Stack3DIcon,
} from "@heroicons/react/24/outline";
import { Link } from "@inertiajs/react";

const HomeSpeed = () => {
	return (
		// <div className="mx-auto bg-blue-gray-200 absolute bottom-0 right-0 h-40 w-full flex container">
		<div className="right-2 md:right-20 bottom-10 fixed p-2 md:p-4 ">
			<SpeedDial>
				<SpeedDialHandler>
					<IconButton size="lg" className="rounded-full">
						<PlusIcon className="h-5 w-5 transition-transform group-hover:rotate-45" />
					</IconButton>
				</SpeedDialHandler>
				<SpeedDialContent>
					<Link href={'/'}>
						<SpeedDialAction>
							<HomeIcon className="h-5 w-5" />
						</SpeedDialAction></Link>
					<SpeedDialAction>
						<CogIcon className="h-5 w-5" />
					</SpeedDialAction>
					<SpeedDialAction>
						<Square3Stack3DIcon className="h-5 w-5" />
					</SpeedDialAction>
				</SpeedDialContent>
			</SpeedDial>
			{/* </div> */}
		</div>
	)
}

export default HomeSpeed;