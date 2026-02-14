import { usePage } from "@inertiajs/react"

const usePageProps = () => {
	const { props } = usePage();
	return props as any;
}

export default usePageProps;