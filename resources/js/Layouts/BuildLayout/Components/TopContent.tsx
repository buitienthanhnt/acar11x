import React, { useMemo } from "react";
import {
    Navbar as MTNavbar,
    Collapse,
    Button,
    IconButton,
    Typography,
} from "@material-tailwind/react";

import {
    XMarkIcon,
    Bars3Icon,
} from "@heroicons/react/24/solid";
import { Link, usePage } from "@inertiajs/react";
import MenuIcon from "./MenuIcon";

function NavItem({ children, href }) {
    return (
        <li>
            <Link
                as="a"
                href={href || "#"}
                target={href ? "_blank" : "_self"}
                variant="paragraph"
                color="gray"
                className="flex items-center gap-2 font-medium text-gray-900"
            >
                {children}
            </Link>
        </li>
    );
}

function TopContent({ }) {
    const { props: { topMenu, auth: { user } }, } = usePage();
    const [open, setOpen] = React.useState(false);
    function handleOpen() {
        setOpen((cur) => !cur);
    }

    React.useEffect(() => {
        window.addEventListener(
            "resize",
            () => window.innerWidth >= 960 && setOpen(false)
        );
    }, []);

    return (
        <MTNavbar shadow={false} fullWidth className="border-0 sticky top-0 z-50">
            <div className="container mx-auto flex items-center justify-between">
                <Link href={'/'}>
                    <Typography color="black" className="text-lg font-bold">
                        Adoc Global
                    </Typography></Link>
                <ul className="ml-10 hidden items-center gap-8 lg:flex">
                    {topMenu.map(({ name, url: href }, index) => (
                        <MenuElement name={name} href={href} key={index}></MenuElement>
                    ))}
                </ul>
                <div className="hidden items-center gap-2 lg:flex">
                    {user ? // https://inertiajs.com/links
                        <>
                            <Link href={route('logout')} method="POST">
                                <Button variant="text">Log out</Button>
                            </Link>
                            <Link href={route('dashboard')}>
                                <Button variant="text">Dashboard</Button>
                            </Link>
                        </> 
                        :
                        <>
                            <Link href={route('login')}>
                                <Button variant="text">Log in</Button>
                            </Link>
                            <Link href={route('register')}>
                                <Button color="gray">Register</Button>
                            </Link>
                        </>
                    }
                </div>
                <IconButton
                    variant="text"
                    color="gray"
                    onClick={handleOpen}
                    className="ml-auto inline-block lg:hidden"
                >
                    {open ? (
                        <XMarkIcon strokeWidth={2} className="h-6 w-6" />
                    ) : (
                        <Bars3Icon strokeWidth={2} className="h-6 w-6" />
                    )}
                </IconButton>
            </div>
            <Collapse open={open}>
                <div className="container mx-auto mt-3 border-t border-gray-200 px-2 pt-4">
                    <ul className="flex flex-col gap-4">
                        {topMenu.map(({ name, url: href }, index) => (
                            <MenuElement name={name} href={href} key={index}></MenuElement>
                        ))}
                    </ul>
                    <div className="mt-6 mb-4 flex items-center gap-2">
                        {user ?
                            <>
                                <Link href={route('logout')} method="post">
                                    <Button variant="text">Log out</Button>
                                </Link>
                                <Link href={route('dashboard')}>
                                    <Button>Dashboard</Button>
                                </Link>
                            </> 
                            :
                            <>
                                <Link href={route('login')}>
                                    <Button variant="text">Log in</Button>
                                </Link>
                                <a href="https://www.material-tailwind.com/blocks">
                                    <Button color="gray">blocks</Button>
                                </a>
                            </>}
                    </div>
                </div>
            </Collapse>
        </MTNavbar>
    );
}

const MenuElement = ({ name, href }) => {
    const isCurrentRoute = useMemo(() => {
        return '/' + route().current() === href?.toLowerCase();
    }, [])

    return (
        <NavItem key={name} href={href}>
            <MenuIcon name={name} color={isCurrentRoute ? 'red' : undefined}></MenuIcon>
            <span style={{ color: isCurrentRoute ? 'red' : undefined }}>{name}</span>
        </NavItem>
    )
}

export default TopContent;
