import { ref } from "vue";
import { format } from "date-fns";

export function useStatsExchangers() {
  const formSearch = ref({
    from: format(new Date().setMonth(new Date().getMonth() - 1), "dd.MM.yyyy"),
    to: format(new Date(), "dd.MM.yyyy"),
    exchanger_id: null,
    service_id: null,
    status: null,
  });

  return {
    formSearch,
  };
}
