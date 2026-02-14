import usePageProps from "./usePageProps";

const useMessage = (): { success?: string, error?: string, errors?: {[key: string]: string} } => {
	const props = usePageProps();

	return {
		success: props.messages,
		error: props.error,
		errors: props.errors,
	};
}

export default useMessage;