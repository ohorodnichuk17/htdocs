import {IUploadedFile} from "../../../interfaces/forms";

export interface IRegister {
    lastName: string;
    name: string;
    email: string;
    phone: string;
    image: string|undefined;
    password: string;
    password_confirmation: string;
}

export interface IRegisterForm {
    lastName: string;
    name: string;
    email: string;
    phone: string;
    image: IUploadedFile|null;
    password: string;
    password_confirmation: string;
}