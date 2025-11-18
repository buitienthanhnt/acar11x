import { User } from "@/type/auth";
import useProps from "./useProps";

const useAuth = () => {
	const { auth: user } = useProps();

	return {
		user: user?.user as User,
	};
}

export { useAuth };