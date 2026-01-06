import AOS from 'aos'
import 'aos/dist/aos.css'

export default ({ app }) => {
    app.AOS = AOS
    if (typeof window !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
            easing: 'ease-in-out'
        })
    }
}
