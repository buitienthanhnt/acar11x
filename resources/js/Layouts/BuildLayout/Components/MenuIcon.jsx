import { ArrowPathIcon, CommandLineIcon, MagnifyingGlassIcon, RectangleStackIcon, UserCircleIcon } from "@heroicons/react/24/solid";

const MenuIcon = ({ name, color }) => {
    switch (name) {
        case 'page':
            return <RectangleStackIcon className="h-5 w-5" color={color} ></RectangleStackIcon>;
        case 'account':
            return <UserCircleIcon className="h-5 w-5" color={color}></UserCircleIcon>;
        case 'Docs':
            return <CommandLineIcon className="h-5 w-5" color={color}></CommandLineIcon>;
        case 'search':
            return <MagnifyingGlassIcon className="h-5 w-5" color={color}></MagnifyingGlassIcon>;
        default:
            return <ArrowPathIcon className="h-5 w-5" color={color}></ArrowPathIcon>;
    }
}
export default MenuIcon;
