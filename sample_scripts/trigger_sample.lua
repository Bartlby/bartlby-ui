function bartlby_trigger(worker, svc, msg)
-- input: svc table (maybe empty, svc.current_state, svc.last_state, svc.current_output, svc.service_name, svc.server_name)
-- input: worker (worker table -> worker.worker_id, worker.worker_name, worker.mail)
-- you also have a few ENV variables

--if you hit the character limit do a "dofile('asad.lua')"
  
  
end
--if you return a negative value - no trigger will be fired - else
--the bartlby_trigger() will be fired

