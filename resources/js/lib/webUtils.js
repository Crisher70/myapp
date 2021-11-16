export class WebUtils {

    static url(route) {
        return `${window.location.origin}/${route.replace(/^\//, '')}`;
    }

    static api(route){
        return this.url(`api/${route.replace(/^\//, '')}`);
    }
}